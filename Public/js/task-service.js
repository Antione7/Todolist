fetch('http://todolist.local/api/task', {credentials:'include',method: 'GET'}).then( function(response){
    return response.json();
}).then(function(tasks){
    for(let i= 0, c = tasks.length; i < c; i++){
        let tr = document.createElement('tr');
        let row = '<td scope="row">' + (i + 1) +'</td>' +
                '<td colspan="4">' + tasks[i].libelle + '</td>' +
                '<td><button class="btn btn-warning" data-id="' + tasks[i].id + '">Update</button></td>' +
                '<td><button class="btn btn-danger" data-id="' + tasks[i].id + '">Delete</button></td>';
        let lastRow = document.getElementById('last-row');
        let tbody = document.querySelector('tbody');
        tbody.insertBefore(tr,lastRow);
        tr.innerHTML = row;
    }
});

document.querySelector("tbody").addEventListener("click", function(e){
	e.preventDefault();
    let item = e.target;
    
    if(item.tagName === "BUTTON"){
        document.querySelectorAll("tr:not(:last-child)").forEach(function(element){
            element.remove();
        });
    }
    let formData = new FormData();
    switch (item.textContent) {
        case "Add":
            formData.append("libelle", document.querySelector("[name='libelle']").value);
		    fetch('http://todolist.local/api/task', {credentials:'include',method: 'POST', body: formData}).then( function(response){
        	    return response.json();
	        }).then(function(tasks){
                for(let i= 0, c = tasks.length; i < c; i++){
                    let tr = document.createElement('tr');
                    let row = '<td scope="row">' + (i + 1) +'</td>' +
                            '<td colspan="4">' + tasks[i].libelle + '</td>' +
                            '<td><button class="btn btn-warning" data-id="' + tasks[i].id + '">Update</button></td>' +
                            '<td><button class="btn btn-danger" data-id="' + tasks[i].id + '">Delete</button></td>';
                    let lastRow = document.getElementById('last-row');
                    let tbody = document.querySelector('tbody');
                    tbody.insertBefore(tr,lastRow);
                    tr.innerHTML = row;
                }
            });    
            break;
        case "Update":
            formData.append("libelle", document.querySelector("[name='libelle']").value);
            formData.append("id", e.target.dataset.id);
		    fetch('http://todolist.local/api/task', {credentials:'include',method: 'PUT', body: formData}).then( function(response){
        	    return response.json();
	        }).then(function(tasks){
                for(let i= 0, c = tasks.length; i < c; i++){
                    let tr = document.createElement('tr');
                    let row = '<td scope="row">' + (i + 1) +'</td>' +
                            '<td colspan="4">' + tasks[i].libelle + '</td>' +
                            '<td><button class="btn btn-warning" data-id="' + tasks[i].id + '">Update</button></td>' +
                            '<td><button class="btn btn-danger" data-id="' + tasks[i].id + '">Delete</button></td>';
                    let lastRow = document.getElementById('last-row');
                    let tbody = document.querySelector('tbody');
                    tbody.insertBefore(tr,lastRow);
                    tr.innerHTML = row;
                }
            });
            break;
        case "Delete":
		    fetch('http://todolist.local/api/task?id=' + e.target.dataset.id, {credentials:'include',method: 'DELETE'}).then( function(response){
        	    return response.json();
	        }).then(function(tasks){
                for(let i= 0, c = tasks.length; i < c; i++){
                    let tr = document.createElement('tr');
                    let row = '<td scope="row">' + (i + 1) +'</td>' +
                            '<td colspan="4">' + tasks[i].libelle + '</td>' +
                            '<td><button class="btn btn-warning" data-id="' + tasks[i].id + '">Update</button></td>' +
                            '<td><button class="btn btn-danger" data-id="' + tasks[i].id + '">Delete</button></td>';
                    let lastRow = document.getElementById('last-row');
                    let tbody = document.querySelector('tbody');
                    tbody.insertBefore(tr,lastRow);
                    tr.innerHTML = row;
                }
            });
            break;
    
        default:
            break;
    }
});