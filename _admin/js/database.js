function finishOp(jqXHR) {
  console.log(jqXHR);
}

function compress() {
  $.ajax({
    url: '../api/v1/Actions/CompressImages',
    method: 'POST',
    complete: finishOp
  });
}

function removeDone(response) {
  if(response.ok) {
    alert('Old images removed');
  } else {
    alert('Error removing old images');
  }
}

function remove() {
  fetch('../api/v1/Actions/RemoveOldImages', {method: 'POST'}).then(response => {
    removeDone(response);
  });
     
}

function initPage() {
  const removeButton = document.getElementById('removeBttn');
  if(!removeButton) {
    window.setTimeout(initPage, 100);
    return;
  }
  if(removeButton) {
    removeButton.addEventListener('click', remove);
  }
}

window.addEventListener('load', initPage());