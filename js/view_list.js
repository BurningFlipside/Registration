/*global Tabulator*/
/*exported nukeImage*/
function getEndpoint() {
  return 'api/v1/'+document.getElementById('listTable').dataset.endpoint;
}

function renderLogo(cell) {
  let data = cell.getValue();
  return '<img src="'+getEndpoint()+'/'+data+'/logo"/ onerror="nukeImage(this)" style="max-width: 100px; max-height: 100px;">';
}

function nukeImage(img) {
  delete img.onerror;
  img.style.display = 'none';
}

function cellClicked(e, cell) {
  let data = cell.getRow().getData();
  let page = document.getElementById('listTable').dataset.viewpage;
  window.location = page+'?id='+data['_id'];
}

function initPage() {
  let listTable = new Tabulator('#listTable', {
    ajaxURL: getEndpoint()+'?$select=_id,name,teaser',
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100, cellClick: cellClicked},
      {title: 'Name', field: 'name', cellClick: cellClicked},
      {title: 'Teaser', field: 'teaser', cellClick: cellClicked},
    ],
  });
  listTable.on('tableBuilt', () => {
    listTable.setData();
  });
}

window.onload = initPage;
