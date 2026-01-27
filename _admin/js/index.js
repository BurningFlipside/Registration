function gotCamps(camps) {
  const campCountElem = document.getElementById('tcCount');
  if(campCountElem) {
    campCountElem.innerText = camps.length;
  }
  const campDoneCountElem = document.getElementById('tcDoneCount');
  if(campDoneCountElem) {
    campDoneCountElem.innerText = camps.filter(c => c.final).length;
  }
}

function gotArtProjects(artProjects) {
  const artCountElem = document.getElementById('artCount');
  if(artCountElem) {
    artCountElem.innerText = artProjects.length;
  }
  const artDoneCountElem = document.getElementById('artDoneCount');
  if(artDoneCountElem) {
    artDoneCountElem.innerText = artProjects.filter(c => c.final).length;
  }
}

function gotArtCars(artCars) {
  const carCountElem = document.getElementById('dmvCount');
  if(carCountElem) {
    carCountElem.innerText = artCars.length;
  }
}

function gotEvents(events) {
  const eventCountElem = document.getElementById('eventCount');
  if(eventCountElem) {
    eventCountElem.innerText = events.length;
  }
}

function initPage() {
  fetch('../api/v1/camps?$filter=year eq current&$select=name,final').then(response => {
    if (response.ok) {
      return response.json();
    }
    throw new Error('Failed to fetch camps');
  }).then(gotCamps);
  fetch('../api/v1/art?$filter=year eq current&$select=name,final').then(response => {
    if (response.ok) {
      return response.json();
    }
    throw new Error('Failed to fetch art projects');
  }).then(gotArtProjects);
  fetch('../api/v1/dmv?$filter=year eq current&$select=name').then(response => {
    if (response.ok) {
      return response.json();
    }
    throw new Error('Failed to fetch art cars');
  }).then(gotArtCars);
  fetch('../api/v1/event?$filter=year eq current&$select=name').then(response => {
    if (response.ok) {
      return response.json();
    }
    throw new Error('Failed to fetch events');
  }).then(gotEvents);
}


window.addEventListener('load', initPage);