import { useEffect, useState } from 'react';
import { useParams, useNavigate } from "react-router";
import request from "../utils/request";
import { getStatus } from "../utils/statusUtils";

export default function JobApplicationDetails() {
    const { id } = useParams();
    const [jobApplication, setJobApplication] = useState(null);
    const [events, setEvents] = useState(null);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        async function fetchJobApplicationDetails() {
            try {
                const response = await request.get(`/job-application/${id}`);
                setJobApplication(response.data.job_application);
                setEvents(response.data.events);
            } catch (error) {
                console.log('Error fetching job application details', error);
            } finally {
                setLoading(false);
            }
        }

        fetchJobApplicationDetails();
    }, [id]);

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!jobApplication) {
        return <div>No details found for this job application.</div>;
    }

    const parseEventData = (eventDataString) => {
        try {
            const parsedData = JSON.parse(eventDataString);
            const { aggregateId, name, number, version, occurredAt, ...rest } = parsedData;
            return rest;
        } catch (error) {
            console.error('Error parsing event data:', error);
            return {};
        }
    };

    const handleReject = () => {
        navigate(`/job-application/reject/${id}`);
    };

    return (
        <div style={{ paddingTop: "130px", whiteSpace: "pre-wrap" }}>
            <h1>Job Application Details</h1>
            <p><strong>Company:</strong> {jobApplication.company}</p>
            <p><strong>Status:</strong>{' '}
                <span className={getStatus(jobApplication.status).className}>
                    {getStatus(jobApplication.status).status}
                </span>
            </p>
            <p><strong>Submit Date:</strong> {jobApplication.submitDate}</p>
            <p><strong>Comment:</strong> {jobApplication.comment}</p>
            <button onClick={handleReject}>Reject</button>
            <h2>Events</h2>
            {events.length > 0 ? (
                <ul>
                    {events.map((event, index) => {
                        const eventData = parseEventData(event.data);
                        return (
                            <li key={index}>
                                <p><strong>Event:</strong> {getStatus(event.event_name).status}</p>
                                <p><strong>Comment:</strong> {event.comment || 'No comment'}</p>
                                <p><strong>Occurred At:</strong> {event.occurred_at}
                                </p>

                                <h4>Event Data:</h4>
                                <ul>
                                    {Object.entries(eventData).map(([key, value]) => (
                                        <li key={key} >
                                            <strong>{key}:</strong> {value}
                                        </li>
                                    ))}
                                </ul>
                            </li>
                        );
                    })}
                </ul>
            ) : (
                <p>No events available.</p>
            )}
        </div>
    );
}
