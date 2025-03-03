import { useEffect, useState } from 'react';
import request from "../utils/request";

export default function Homepage() {
    const [jobApplications, setJobApplications] = useState([]);

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/job-application/list');
                setJobApplications(response.data.jobApplications);
            } catch (error) {
                console.log('error');
            }
        }

        fetchData();
        }, []);

    const getStatus = (eventName) => {
        switch (eventName) {
            case 'job_application_submitted':
                return 'Submitted';
            case 'job_application_added':
                return 'Added';
            default:
                return 'Unknown Status';
        }
    };

    return (
        <div>
            <h1>Your applications</h1>
            <ul>
                {jobApplications.map((application) => (
                    <li key={application.id}>
                        <strong>{application.company}</strong>: {getStatus(application.eventName)}
                    </li>
                ))}
            </ul>
        </div>
    );
}



