// A tiny wrapper around fetch(), borrowed from
// https://kentcdodds.com/blog/replace-axios-with-a-simple-custom-fetch-wrapper
//import store from '../store'

export async function Client(endpoint, { body, ...customConfig } = {}) {
    const headers = { 'Content-Type': 'application/json' }

    const config = {
      showloading: true,
      method: body ? 'POST' : 'GET',
      ...customConfig,
      headers: {
        ...headers,
        ...customConfig.headers,
      },
    }
  
    if (body) {
        config.body = JSON.stringify(body)
    }
  

    let data
    try {
        const response = await window.fetch(endpoint, config)
        data = await response.json()

        if (response.ok) {
            return {status: true, data: data}
        }
        throw new Error(response.statusText)
    } catch (err) {
        
        return Promise.reject({status: false, message: data.message})
        
    }finally{
        //To check if we need to show loading

    }
  }
  
  Client.get = function (endpoint, customConfig = {}) {
    return Client(endpoint, { ...customConfig, method: 'GET' })
  }
  
  Client.post = function (endpoint, body, customConfig = {}) {
    return Client(endpoint, { ...customConfig, body })
  }

  Client.put = (endpoint, body, customConfig = {}) => {
    return Client(endpoint, {...customConfig, method: "PUT", body})
  }

  Client.delete = (endpoint, body, customConfig = {}) => {
    return Client(endpoint, {...customConfig, method: "DELETE", body})
  }
  