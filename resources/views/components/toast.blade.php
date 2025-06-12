@props(['type' => 'success'])

<span {{ $attributes->merge([
    'class' => "alert alert-$type",
    'style' => 'position: absolute; top: 10px; right: 10px; z-index: 999;',
    'id' => 'toast',
]) }} role="alert">
        @if(session("success"))
        {{session("success")}}

    @endif
    @if(session("error"))
        {{session("error")}}
    @endif

</span>

<script>
    setTimeout(() => {
        const toast = document.getElementById("toast");
        if (toast) {
            toast.style.display = "none";
        }
    }, 2000);
</script>
