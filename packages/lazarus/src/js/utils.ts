
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

export const setUrlParam = (key: string, value: any) => {
  const url = new URL(window.location.href);
  url.searchParams.set(key, String(value));
  window.history.pushState({ path: url.href }, '', url.href);
}

export const getUrlParam = (key: string, fallback: any = null) => {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const value = urlParams.get(key)
  return value ? value : fallback;
}