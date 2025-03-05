import { useState } from 'react';
import request from "../utils/request";

export default function AddJobApplication({ onAddSuccess }) {
    const [companyName, setCompanyName] = useState('');
    const [positionTitle, setPositionTitle] = useState('');
    const [jobDescription, setJobDescription] = useState('');
    const [comment, setComment] = useState('');
    const [errorMessage, setErrorMessage] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();

        const data = {
            company: companyName,
            position: positionTitle,
            details: jobDescription,
            comment: comment
        };

        try {
            const response = await request.post('/job-application/add', data, {
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            // If successful, handle success response
            if (response.status === 201) {
                const jobId = response.data.id;
                onAddSuccess(jobId);
            }
        } catch (error) {
            setErrorMessage('There was an error adding the job application.');
        }
    };

    return (
        <div>
            <h1>Add Job Application</h1>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Company Name:</label>
                    <input
                        type="text"
                        value={companyName}
                        onChange={(e) => setCompanyName(e.target.value)}
                        required
                    />
                </div>

                <div>
                    <label>Position Title:</label>
                    <input
                        type="text"
                        value={positionTitle}
                        onChange={(e) => setPositionTitle(e.target.value)}
                        required
                    />
                </div>

                <div>
                    <label>Job Description:</label>
                    <textarea
                        value={jobDescription}
                        onChange={(e) => setJobDescription(e.target.value)}
                        required
                    />
                </div>

                <div>
                    <label>Comment:</label>
                    <textarea
                        value={comment}
                        onChange={(e) => setComment(e.target.value)}
                    />
                </div>

                <button type="submit">Add Job Application</button>
            </form>

            {errorMessage && <div style={{color: 'red'}}>{errorMessage}</div>}
        </div>
    );
};
