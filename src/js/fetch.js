function fetchRequest(which, action, method, body = '') {
  const URL = `https://cinemalo.000webhostapp.com/DataManager/${whichUrl(which)}Controller.php?action=${action}` ;

  if (method === 'POST') {
    return fetch(URL, {
      method: method,
      body: body
    })
  }
  return fetch(URL, {
    method: method,
  })
}

function whichUrl(which) {
  return which.toLowerCase()[0].toUpperCase() + which.toLowerCase().slice(1)
}

export default fetchRequest