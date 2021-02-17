// Upload file from browser
function upload() {
	var ins = document.getElementById('inputfile').files.length;
	var files = [];
	for (var x = 0; x < ins; x++) {
		files.push(document.getElementById('inputfile').files[x]);
	}
	upload_files( files );
	return false;
}


// Upload file from files list array
file_id = 0;
function upload_files(files) {
	let url = '/'
	let formData = new FormData()
	var uploaded = document.getElementById('uploaded');
	formData.append('json', 'true');

	for ( var i = 0; i < files.length; i++ )
	{
		var node = document.createElement('li');
		node.innerText = 'Uploading...';
		node.classList.add('uploading');
		node.id = 'file_' + ++file_id;
		uploaded.appendChild(node);
		formData.append('file_' + file_id, files[i]);
	}

	fetch(url, {
		method: 'POST',
		body: formData
	})
	.then((r) => {
		var dropper = document.getElementById('dropper');
		var dropzone = document.getElementById('dropzone');
		dropper.classList.remove('active');
		dropzone.classList.remove('active');

		return r.json();
	})
	.then((json) => {
		var files = json;
		
		for ( var file_key in files ) {
			var file_progress = document.querySelector('#' + file_key);
			file_progress.innerHTML = '<a target="_blank" href="' + files[file_key].url + '">' + files[file_key].url + '</a> ' + files[file_key].size + ' bytes';
			file_progress.classList.remove('uploading');
		}

		var elements = document.getElementsByClassName('uploading');
		for ( var i = 0; i < elements.length; i++ ) {
			elements[i].innerText = 'Hmm';
		}
	})
	.catch(() => {
		var dropper = document.getElementById('dropper');
		var dropzone = document.getElementById('dropzone');
		dropper.classList.remove('active');
		dropzone.classList.remove('active');

		var elements = document.getElementsByClassName('uploading');
		for ( var i = 0; i < elements.length; i++ ) {
			elements[i].innerText = 'Failed :(';
		}
	});
}


// Dumb but simple upload progress animation
function uploading() {
	var elements = document.getElementsByClassName('uploading');
	for ( var i = 0; i < elements.length; i++ ) {
		if ( elements[i].innerText == 'Uploading...' ) elements[i].innerText = 'Uploading';
		else if ( elements[i].innerText == 'Uploading' ) elements[i].innerText = 'Uploading.';
		else if ( elements[i].innerText == 'Uploading.' ) elements[i].innerText = 'Uploading..';
		else elements[i].innerText = 'Uploading...';
	}

	setTimeout(uploading, 1000);
}


// Initialize uploader and drag and drop listeners
function init_uploads() {
	var dropper = document.getElementById('dropper');
	var dropzone = document.getElementById('dropzone');
	
	dropzone.addEventListener('dragleave', function(e) {
		dropper.classList.remove('active');
		dropzone.classList.remove('active');
		e.preventDefault();
  		e.stopPropagation();
	}, false);
	
	document.body.addEventListener('dragover', function(e) {
		dropper.classList.add('active');
		dropzone.classList.add('active');
		e.preventDefault();
  		e.stopPropagation();
	}, false);

	dropzone.addEventListener('drop', function(e) {
		upload_files(e.dataTransfer.files);

		dropper.classList.remove('active');
		dropzone.classList.remove('active');

		e.preventDefault();
  		e.stopPropagation();
	}, false);

	uploading();
}



// Utilities
function copy_code_to_clipboard() {
  var txt = document.getElementById("copy");
  txt.value = document.querySelector('code').innerText;
  
  txt.select();
  txt.setSelectionRange(0, 99999);
  
  document.execCommand("copy");
  document.querySelector('#browser-header').innerText = 'Code copied to buffer successfully';
}