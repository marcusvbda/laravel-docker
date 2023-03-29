
export const makeRequestHeader = () => {
  return {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }
}

export const request = (method: string, url: string, body: any) => {
  return fetch(url, {
    method,
    body: JSON.stringify(body),
    headers: makeRequestHeader()
  }).then(res => res.json())
}

export const resourceResolver = (body: any, callback: any) => {
  request('POST', route('lazarus.resolve-resource-component'), body).then(callback);
}