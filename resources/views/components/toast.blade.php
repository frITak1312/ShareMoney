@props(['type' => 'success'])

<span {{ $attributes->merge([
    'class' => "alert alert-$type toast-message",
    'style' => 'position: absolute; bottom: 10px; left: 10px; z-index: 999;',
]) }} role="alert">
    @if(session("success"))
        {{ session("success") }}
    @endif
    @if(session("error"))
        {{ session("error") }}
    @endif
</span>

<script>
    setTimeout(() => {
        document.querySelectorAll(".toast-message").forEach(el => {
            el.style.display = "none";
        });
    }, 2000);
</script>
