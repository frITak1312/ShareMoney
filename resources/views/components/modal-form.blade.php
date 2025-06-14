@props(['heading', 'action' => null])

<div class='modal fade' id="{{ $attributes->get('id') }}" tabindex='-1' aria-labelledby='modalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content text-center'>
            <div class='modal-header'>
                <h5 class='modal-title' id='modalLabel'>{{$heading}}</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Zavřít'></button>
            </div>
            <div class='modal-body'>
                {{-- Výpis chybové hlášky pokud je určena pro tento modal --}}
                @if(session('error') && session('modal') === $attributes->get('id'))
                    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                @endif
                <x-form method='post' :action="$action">
                    {{$slot}}
                </x-form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalId = @json($attributes->get('id'));
        const modalElement = document.getElementById(modalId);

        if (modalElement) {
            // třídy všem inputům v modalu
            const inputs = modalElement.querySelectorAll("input");
            inputs.forEach(input => {
                input.classList.add("mb-3", "container-fluid");
            });

            modalElement.addEventListener("hidden.bs.modal", function() {
                const errorDiv = modalElement.querySelector(".alert-danger");
                if (errorDiv) errorDiv.style.display = "none";
            });
            //Reopen modalu pokud je v session
            @if(session('modal') === $attributes->get('id'))
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            @endif
        }
    });
</script>
