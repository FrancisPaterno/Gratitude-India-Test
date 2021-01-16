const axios = ()=>impport('axios')
let BaseApi = axios.create({baseURL:"http://127.0.0.1/api"})

const Api = function(){
    return BaseApi;
}

