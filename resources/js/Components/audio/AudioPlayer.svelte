<script>
    import { popup } from '@skeletonlabs/skeleton';
    import { onMount } from 'svelte';

    import { toFixed } from '@/lib/time';
    import { Volume, Mute, Loop, Stop, Speedometer } from '@/Components/icons';
    import MediaControls from '@/Components/audio/MediaControls.svelte';

    /**
     * @typedef {Object} Props
     * @property {any} src
     * @property {string} [title]
     * @property {boolean} [loop] - export let crossorigin;
     */

    /** @type {Props} */
    let { src, title, loop = true, preload = 'auto' } = $props();

    const barWidth = 3;
    const gap = 2;
    const barColor = [32, 211, 238]; // rgb, tertiary-500

    let audio = $state(); // MediaElement
    let audioCtx = new AudioContext();
    let track;
    let gainNode;
    let analyserNode;
    let canvas = $state();
    let canvasCtx;

    let isPlaying = $state();
    let currentTime = $state();
    let duration = $state();
    let bufferLength;
    let dataArray;

    // If src ever changes, initailize
    // $: src && initializeAudio();
    onMount(async () => {
        initializeAudio();
    });

    function initializeAudio() {
        track = audioCtx.createMediaElementSource(audio);
        gainNode = audioCtx.createGain();
        analyserNode = audioCtx.createAnalyser();
        analyserNode.fftSize = 2048;
        bufferLength = analyserNode.frequencyBinCount;
        dataArray = new Uint8Array(bufferLength);
        track
            .connect(gainNode)
            .connect(analyserNode)
            .connect(audioCtx.destination);
        canvasCtx = canvas.getContext('2d');
    }

    // This is called per frame of monitor refresh rate
    // Be careful
    function updateFrequency() {
        if (!isPlaying) return;
        analyserNode.getByteFrequencyData(dataArray);

        canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
        canvasCtx.fillStyle = 'rgba(37,40,42,0)'; // bg base-700
        canvasCtx.fillRect(0, 0, canvas.width, canvas.height);

        const barCount = bufferLength / (barWidth + gap - 1);
        for (let i = 0, x = 0; i < barCount; ++i, x += barWidth + gap) {
            const perc = (dataArray[i] * 100) / 255; // Percantage scaled to 0-255
            const h = (perc * canvas.height) / 100;
            // Take our color, double it, multiply by freq data, normalize by 255
            canvasCtx.fillStyle = `rgba(${barColor.map((v) => (v * 2 * dataArray[i]) / 255).join(', ')}, 1)`;
            canvasCtx.fillRect(x, canvas.height - h, barWidth, canvas.height);
        }

        requestAnimationFrame(updateFrequency.bind(this));
    }

    function formatTime(time) {
        if (!time) return '0:00';
        const secs = `${parseInt(`${time % 60}`, 10)}`.padStart(2, '0');
        const mins = parseInt(`${(time / 60) % 60}`, 10);
        const hours = parseInt(`${(time / 3600) % 60}`, 10);

        return (
            hours ? [hours, `${mins}`.padStart(2, '0'), secs] : [mins, secs]
        ).join(':');
    }

    async function ended() {
        if (loop) {
            audio.currentTime = 0;
            await audio.play();
        } else {
            isPlaying = false;
        }
    }

    async function playpause() {
        if (audioCtx.state === 'suspended') {
            await audioCtx.resume();
        }

        if (isPlaying) {
            await audio.pause();
            isPlaying = false;
        } else {
            await audio.play();
            isPlaying = true;
            updateFrequency();
        }
    }

    function seek(value) {
        let target = audio.currentTime + value;
        if (target < 0) target = 0;
        if (target > duration) target = duration;
        audio.currentTime = target;
    }

    function seekTo(value) {
        audio.currentTime = value;
    }

    let volume = $state(0.5);
    let isVolumeVisible = $state(false);
    let previousVolume = $state(0.5);
    function changeVolume(value) {
        volume = value;
        previousVolume = value;
    }
    $effect(() => {
        gainNode.gain.value = volume;
    });

    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    let volumePopup = {
        event: 'focus-click',
        target: 'volumeSliderPopup',
        placement: 'right',
        state: ({ state }) => {
            isVolumeVisible = state;
            if (!state) clickCount = 0;
        },
    };

    let clickCount = $state(0);
    function handleVolume() {
        if (isVolumeVisible && clickCount >= 1) {
            // Volume is visible, clicking mutes
            if (volume > 0) {
                previousVolume = volume;
                volume = 0;
                return;
            }
            // Volume is visible, clicking unmute
            if (volume <= 0) {
                volume = previousVolume;
                return;
            }
        }
        clickCount++;
    }

    /** @type {import('@skeletonlabs/skeleton').PopupSettings} */
    let speedPopup = {
        event: 'focus-click',
        target: 'speedSliderPopup',
        placement: 'left',
    };
    const maxSpeed = 3;
    const minSpeed = 1 / maxSpeed;
    let speedSliderValue = $state(toSpeedSlider(1));
    let speed = $state(1);
    function toSpeedActual(slider) {
        return Math.exp(
            Math.log(minSpeed) +
                slider * (Math.log(maxSpeed) - Math.log(minSpeed))
        );
    }
    function toSpeedSlider(actual) {
        return (
            (Math.log(actual) - Math.log(minSpeed)) /
            (Math.log(maxSpeed) - Math.log(minSpeed))
        );
    }
    $effect(() => (speed = toSpeedActual(speedSliderValue)));

    let isShiftHeld = false;
    function onKeyDown(e) {
        if (e.repeat) return;
        if (e.key === 'Shift') return (isShiftHeld = true);
        if (['input', 'textarea'].includes(e.target.tagName.toLowerCase()))
            return;
        switch (e.code) {
            case 'Space':
            case 'Enter':
                e.preventDefault();
                return playpause();
            case 'ArrowRight':
                e.preventDefault();
                return seek(isShiftHeld ? 5 : 1);
            case 'ArrowLeft':
                e.preventDefault();
                return seek(isShiftHeld ? -5 : -1);
        }
    }
    function onKeyUp(e) {
        if (e.key === 'Shift') isShiftHeld = false;
    }
</script>

<svelte:window onkeydown={onKeyDown} onkeyup={onKeyUp} />
<figure
    class="card flex w-full max-w-lg flex-col justify-evenly gap-2 rounded pt-2 text-center font-sans">
    {#if title}
        <figcaption
            class="w-full rounded-sm bg-inherit px-2 py-1 text-center font-normal capitalize">
            {title}
        </figcaption>
    {/if}
    <audio
        {src}
        {preload}
        bind:this={audio}
        bind:duration
        bind:currentTime
        bind:playbackRate={speed}
        onended={ended}>
        Your browser does not support the audio element.
    </audio>

    <canvas bind:this={canvas} class="h-8 w-full"></canvas>

    <div class="flex items-center">
        <div class="flex flex-1 items-center justify-end gap-2">
            <span>{formatTime(currentTime)}</span>
            <input
                type="range"
                max={duration}
                value={currentTime}
                oninput={(e) => seekTo(e.target.value)} />
            <span>{formatTime(duration)}</span>
        </div>
    </div>

    <div class="grid grid-cols-5 items-center justify-between">
        <div>
            <button
                title="Playback speed"
                type="button"
                class="flex items-center gap-1"
                use:popup={speedPopup}>
                <Speedometer />
                <span>x{toFixed(speed, 2)}</span>
            </button>
            <div data-popup="speedSliderPopup">
                <input
                    class="mr-4 h-24 [writing-mode:bt-lr]"
                    type="range"
                    min="0"
                    max="1"
                    step=".01"
                    bind:value={speedSliderValue}
                    orient="vertical" />
            </div>
        </div>
        <MediaControls {playpause} {seek} class="col-span-3" />
        <div class="flex justify-between text-right">
            <button
                onclick={() => (loop = !loop)}
                title="Toggle loop at end of track">
                {#if loop}
                    <Loop />
                {:else}
                    <Stop />
                {/if}
            </button>
            <div>
                <button
                    title="Volume"
                    type="button"
                    class="h-8 w-8 min-w-8"
                    use:popup={volumePopup}
                    onclick={handleVolume}>
                    {#if volume > 0}
                        <Volume />
                    {:else}
                        <Mute />
                    {/if}
                </button>
                <div data-popup="volumeSliderPopup">
                    <input
                        class="ml-2 h-24 [writing-mode:bt-lr]"
                        type="range"
                        min="0"
                        max="2"
                        step="0.01"
                        value={volume}
                        orient="vertical"
                        oninput={(e) => changeVolume(e.target.value)} />
                </div>
            </div>
        </div>
    </div>
</figure>
