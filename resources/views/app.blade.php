@includeWhen(App::isProduction(), 'gnome')
<!DOCTYPE html>
<html class="dark">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha_v3.sitekey')}}"></script>
    <script>
        window.grecaptcha_sitekey = "{{config('services.recaptcha_v3.sitekey')}}";
    </script>
    @vite('resources/js/app.js')
    @inertiaHead
  </head>
  {{-- Set background twice, one for ref, style is there so body is dark while app boots when ssr down --}}
  <body data-theme="abidan" class="bg-surface-900" style="background-color: rgb(13, 19, 26);">
    <div style="display: contents" class="h-full overflow-hidden">
        @inertia
    </div>
  </body>
</html>
