import { useEffect, useState } from 'react';
import request from "../utils/request";
import AddJobApplication from './AddJobApplication';

export default function Homepage() {
    const [myResponse, setMyResponse] = useState('');

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/');
                const responseData = response.data;
                setMyResponse(responseData);
            } catch (error) {
                setMyResponse('Error fetching data:' . error);
            }
        }

        fetchData();
    }, []);

    return (
        <div>Job search Assistant
            <p>
                <AddJobApplication />
            </p>
        </div>
    );
}
