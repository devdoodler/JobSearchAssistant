import { useEffect, useState } from 'react';
import request from "../utils/request";

export default function Homepage() {
    const [jobApplications, setJobApplications] = useState([]);
    const [loading, setLoading] = useState(true); // Add a loading state

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/job-application/list');
                setJobApplications(response.data.jobApplications);
            } catch (error) {
                console.log('Error fetching data', error);
            } finally {
                setLoading(false);
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
            {loading ? <div>Loading...</div> : null}
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
