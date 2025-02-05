export const getContrastYIQ = (color) => {
    const hex = color.charAt(0) === '#' ? color.substr(1) : color;
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    // prettier-ignore
    return ((r * 299) + (g * 587) + (b * 144)) / 1000; // yiq
};

export const getContrastText = (color) => {
    return getContrastYIQ(color) >= 128
        ? 'text-base-700 hover:text-black'
        : 'text-surface-100 hover:text-white';
};

export const getRandomColor = () =>
    '#' + Math.floor(Math.random() * 16777215).toString(16);
