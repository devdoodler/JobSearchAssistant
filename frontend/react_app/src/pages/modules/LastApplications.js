import { useEffect, useState } from 'react';
import { useNavigate } from "react-router";
import request from "../../utils/request";
import { getStatus } from "../../utils/statusUtils";
import { Card, Button, ListGroup, Spinner } from 'react-bootstrap';

export default function LastApplications() {
    const [jobApplications, setJobApplications] = useState([]);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/job-application/list/submit/3');
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

    function ApplicationItemRow({application}) {
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
    }

    function ApplicationDateRow({ date }) {
        return (
            <ListGroup.Item
                key={date }
                className="d-flex justify-content-start align-items-center"
            >
                <div>{date}</div>
            </ListGroup.Item>
        );
    }
    function ApplicationList({ applications }) {
        const rows = [];
        let lastDate = null;
        let submitDate = null;

        {applications.map((application) => {
            submitDate = application.submitDate.split(' ')[0];
            if (lastDate !== submitDate) {
                rows.push(<ApplicationDateRow date={submitDate} />);
            }
            rows.push(<ApplicationItemRow application={application} />);
            lastDate = submitDate;
        })}

        return (
            <ListGroup>
                {rows}
            </ListGroup>
        );

    }

    return (
        <div>
            <h2>Last applications</h2>
            {loading ? (
                <div className="text-center">
                    <Spinner animation="border" role="status" />
                    <span className="sr-only">Loading...</span>
                </div>
            ) : (
                <ApplicationList applications={jobApplications} />
            )}
        </div>
    );
}
