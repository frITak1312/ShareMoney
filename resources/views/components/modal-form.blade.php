@props(['heading', 'action'])

<div class='modal fade' id='myModal' tabindex='-1' aria-labelledby='modalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='modalLabel'>{{$heading}}</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Zavřít'></button>
            </div>
            <div class='modal-body'>
                <x-form method='post' :action="$action">
                    {{$slot}}
                </x-form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalBody = document.querySelector(".modal-body");
        const inputs = modalBody.querySelectorAll("input");

        inputs.forEach(input => {
            input.classList.add("form-control", "mb-3");
        });
    });
</script>
