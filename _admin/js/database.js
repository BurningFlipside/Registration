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
