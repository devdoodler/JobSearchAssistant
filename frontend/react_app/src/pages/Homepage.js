import { useEffect, useState } from 'react';
import { useNavigate } from "react-router";
import request from "../utils/request";

export default function Homepage() {
    const [jobApplications, setJobApplications] = useState([]);
    const [loading, setLoading] = useState(true); // Add a loading state
    const navigate = useNavigate();

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
            case 'job_application_rejected':
                return 'Rejected';
            default:
                return 'Unknown Status';
        }
    };

    const handleCompanyClick = (id) => {
        navigate(`/job-application/${id}`);
    };

    return (
        <div>
            <h1>Your applications</h1>
            {loading ? <div>Loading...</div> : null}
            <ul>
                {jobApplications.map((application) => (
                    <li key={application.id}>
                        <strong
                            style={{cursor: 'pointer', color: 'blue'}}
                            onClick={() => handleCompanyClick(application.id)}
                        >
                            {application.company}
                        </strong>
                        : {getStatus(application.eventName)}
                    </li>
                ))}
            </ul>
        </div>
);
}
