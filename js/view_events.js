/*global jQuery, Tabulator, XLSX*/
/*exported nukeImage*/
function getEndpoint() {
  return 'api/v1/event';
}

function renderLogo(cell) {
  let data = cell.getValue();
  return '<img src="'+getEndpoint()+'/'+data+'/logo"/ onerror="nukeImage(this)" style="max-width: 100px; max-height: 100px;">';
}

function renderTime(cell, params) {
  let date = params.map[params.day];
  let data = cell.getValue();
  if(data === 'all') {
    return 'All Day';
  }
  if(data === '?') {
    return '?';
  }
  data = data/100;
  date.setHours(data);
  return date.toLocaleTimeString();
}

function renderTooltip(e, cell) {
  let data = cell.getRow().getData();
  let tooltip = data['description'];
  return tooltip;

}

function nukeImage(img) {
  delete img.onerror;
  jQuery(img).hide();
}

function rowClicked(e, row) {
  let data = row.getData();
  window.location = 'view_event.php?id='+data['_id'];
}

function getDayDateMap() {
  let now = new Date();
  let memorialDay = new Date(now.getFullYear(), 5, 0);
  let weekDay = memorialDay.getDay();
  memorialDay.setDate(memorialDay.getDate() - (weekDay < 1 ? 6 : weekDay - 1));
  let map = {};
  const days = ['Monday', 'Sunday', 'Saturday', 'Friday', 'Thursday'];
  for(let day of days) {
    // eslint-disable-next-line security/detect-object-injection
    map[day] = memorialDay;
    memorialDay.setDate(memorialDay.getDate() - 1);
  }
  return map;
}

function exportExcel() {
  let tables = Tabulator.findTable('.tabulator');
  const workbook = XLSX.utils.book_new();
  for(let table of tables) {
    let data = table.getData();
    const filtered = data.map((item) => {
      return {
        Name: item.name,
        Teaser: item.teaser,
        Description: item.description,
        Start: item.start,
        End: item.end,
        Location: item.location,
      };
    });
    let workSheet = XLSX.utils.json_to_sheet(filtered);
    let name = table.element.id.slice(0, -5);
    XLSX.utils.book_append_sheet(workbook, workSheet, name);
  }
  XLSX.writeFile(workbook, 'events.xlsx', {compression: true});
}

function initPage() {
  let dateMap = getDayDateMap();
  let ThursdayTable = new Tabulator('#ThursdayTable', {
    ajaxURL: getEndpoint()+'?$filter=Thursday eq true and year eq current&$select=_id,name,teaser,description,start,end,location',
    columnDefaults:{
      tooltip: renderTooltip,
    },
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100},
      {title: 'Name', field: 'name'},
      {title: 'Start Time', field: 'start', formatter: renderTime, formatterParams: {map: dateMap, day: 'Thursday'}},
      {title: 'End Time', field: 'end', formatter: renderTime, formatterParams: {map: dateMap, day: 'Thursday'}},
      {title: 'Teaser', field: 'teaser'},
    ],
  });
  ThursdayTable.on('tableBuilt', () => {
    ThursdayTable.setData();
  });
  ThursdayTable.on('rowClick', rowClicked);
  let FridayTable = new Tabulator('#FridayTable', {
    ajaxURL: getEndpoint()+'?$filter=Friday eq true and year eq current&$select=_id,name,teaser,description,start,end,location',
    columnDefaults:{
      tooltip: renderTooltip,
    },
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100},
      {title: 'Name', field: 'name'},
      {title: 'Start Time', field: 'start', formatter: renderTime, formatterParams: {map: dateMap, day: 'Friday'}},
      {title: 'End Time', field: 'end', formatter: renderTime, formatterParams: {map: dateMap, day: 'Friday'}},
      {title: 'Teaser', field: 'teaser'},
    ],
  });
  FridayTable.on('tableBuilt', () => {
    FridayTable.setData();
  });
  FridayTable.on('rowClick', rowClicked);
  let SaturdayTable = new Tabulator('#SaturdayTable', {
    ajaxURL: getEndpoint()+'?$filter=Saturday eq true and year eq current&$select=_id,name,teaser,description,start,end,location',
    columnDefaults:{
      tooltip: renderTooltip,
    },
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100},
      {title: 'Name', field: 'name'},
      {title: 'Start Time', field: 'start', formatter: renderTime, formatterParams: {map: dateMap, day: 'Saturday'}},
      {title: 'End Time', field: 'end', formatter: renderTime, formatterParams: {map: dateMap, day: 'Saturday'}},
      {title: 'Teaser', field: 'teaser'},
    ],
  });
  SaturdayTable.on('tableBuilt', () => {
    SaturdayTable.setData();
  });
  SaturdayTable.on('rowClick', rowClicked);
  let SundayTable = new Tabulator('#SundayTable', {
    ajaxURL: getEndpoint()+'?$filter=Sunday eq true and year eq current&$select=_id,name,teaser,description,start,end,location',
    columnDefaults:{
      tooltip: renderTooltip,
    },
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100},
      {title: 'Name', field: 'name'},
      {title: 'Start Time', field: 'start', formatter: renderTime, formatterParams: {map: dateMap, day: 'Sunday'}},
      {title: 'End Time', field: 'end', formatter: renderTime, formatterParams: {map: dateMap, day: 'Sunday'}},
      {title: 'Teaser', field: 'teaser'},
    ],
  });
  SundayTable.on('tableBuilt', () => {
    SundayTable.setData();
  });
  SundayTable.on('rowClick', rowClicked);
  let MondayTable = new Tabulator('#MondayTable', {
    ajaxURL: getEndpoint()+'?$filter=Monday eq true and year eq current&$select=_id,name,teaser,description,start,end,location',
    columnDefaults:{
      tooltip: renderTooltip,
    },
    rowHeight: 100,
    columns: [
      {title: 'Logo', field: '_id', formatter: renderLogo, width: 100},
      {title: 'Name', field: 'name'},
      {title: 'Start Time', field: 'start', formatter: renderTime, formatterParams: {map: dateMap, day: 'Monday'}},
      {title: 'End Time', field: 'end', formatter: renderTime, formatterParams: {map: dateMap, day: 'Monday'}},
      {title: 'Teaser', field: 'teaser'},
    ],
  });
  MondayTable.on('tableBuilt', () => {
    MondayTable.setData();
  });
  MondayTable.on('rowClick', rowClicked);
  let div = document.createElement('div');
  div.id = 'exportButton';
  div.style.textAlign = 'right';
  div.style.float = 'right';
  let button = document.createElement('button');
  button.innerHTML = '<i class="far fa-file-excel"></i>';
  button.onclick = exportExcel;
  button.title = 'Export to Excel';
  button.className = 'btn btn-link';
  div.appendChild(button);
  let tabContent = document.getElementById('myTabContent');
  tabContent.parentElement.appendChild(div);
}

window.onload = initPage;
