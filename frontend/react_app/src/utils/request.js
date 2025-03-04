// request.js
import axios from 'axios';

const baseURL = 'http://localhost';

const request = axios.create({
   baseURL,
   headers: {
      'Cache-Control': 'no-store, no-cache, must-revalidate',
   },
});

export default request;
