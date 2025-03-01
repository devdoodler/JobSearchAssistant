import { useState } from 'react';
import request from "../utils/request";

const AddJobApplication = () => {
    const [companyName, setCompanyName] = useState('');
    const [positionTitle, setPositionTitle] = useState('');
    const [jobDescription, setJobDescription] = useState('');
    const [successMessage, setSuccessMessage] = useState('');
    const [errorMessage, setErrorMessage] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();

        const data = {
            company: companyName,
            position: positionTitle,
            details: jobDescription,
        };

        try {
            // Send POST request to backend API to add job application
            const response = await request.post('/job-application/add', data, {
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            // If successful, handle success response
            if (response.status === 201) {
                setSuccessMessage('Job application added successfully!');
                setErrorMessage('');
                setCompanyName('');
                setPositionTitle('');
                setJobDescription('');
            }
        } catch (error) {
            setErrorMessage('There was an error adding the job application.');
            setSuccessMessage('');
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

                <button type="submit">Add Job Application</button>
            </form>

            {successMessage && <div style={{ color: 'green' }}>{successMessage}</div>}
            {errorMessage && <div style={{ color: 'red' }}>{errorMessage}</div>}
        </div>
    );
};

export default AddJobApplication;
