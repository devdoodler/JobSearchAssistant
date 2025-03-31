import { useEffect, useState } from 'react';
import { useNavigate } from "react-router";
import request from "../utils/request";
import { getStatus } from "../utils/statusUtils";
import { Card, Button, ListGroup, Spinner } from 'react-bootstrap';

export default function JobApplicationList() {
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

    const handleCompanyClick = (id) => {
        navigate(`/job-application/${id}`);
    };

    return (
        <div style={{ paddingTop: "130px" }}>
            <h1>Your applications</h1>
            {loading ? (
                <div className="text-center">
                    <Spinner animation="border" role="status" />
                    <span className="sr-only">Loading...</span>
                </div>
            ) : (
                <ListGroup>
                    {jobApplications.map((application) => {
                        const { status, className } = getStatus(application.eventName);
                        return (
                            <ListGroup.Item
                                key={application.id}
                                className="d-flex justify-content-start align-items-center"
                            >
                                <span style={{ marginRight: '10px' }}>•</span>
                                <div
                                    style={{ cursor: 'pointer', color: 'blue' }}
                                    onClick={() => handleCompanyClick(application.id)}
                                >
                                    {application.company}{' '}
                                    <span className={className}>
                                        {status}
                                    </span>
                                </div>
                            </ListGroup.Item>
                        );
                    })}
                </ListGroup>
            )}
        </div>
    );
}
