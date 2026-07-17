<script {{ $attributes }}>
    (function () {
        const form = document.currentScript.previousElementSibling;
        let isDirty = false;

        form.addEventListener('input', () => (isDirty = true));
        form.addEventListener('submit', () => (isDirty = false));

        window.addEventListener('beforeunload', (e) => {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    })();
</script>
