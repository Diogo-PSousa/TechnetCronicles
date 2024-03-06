
    function handleFormSubmitPatch(event, action, userId) {
        event.preventDefault();

        if (!confirm("Are you sure?")) {
            return false;
        }

        const url = `/admin/${action}/${userId}`;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ _method: 'PATCH' }) 
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

        })
        .catch(error => {
            console.error('Error:', error);
        });

        return false;
    }

    function handleFormSubmitDelete(event, action, userId) {
        event.preventDefault();

        if (!confirm("Are you sure?")) {
            return false;
        }

        const url = `/admin/${action}/${userId}`;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ _method: 'DELETE' }) 
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);

        })
        .catch(error => {
            console.error('Error:', error);
        });

        return false;
    }

