import axios from 'axios';

const baseURL= 'http://localhost';

const request = axios.create({
   baseURL,
});

export default request;
