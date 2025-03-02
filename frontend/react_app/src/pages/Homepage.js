import { useState } from 'react';
import { useNavigate } from "react-router";  // React Router for navigation
import AddJobApplication from './AddJobApplication';

export default function Homepage() {
    const [jobApplicationId, setJobApplicationId] = useState(null);
    const navigate = useNavigate();

    const handleAddSuccess = (jobId) => {
        setJobApplicationId(jobId);
        navigate(`/job-application/submit/${jobId}`);
    };

    return (
        <div>
            <h1>Job Search Assistant</h1>

            <AddJobApplication onAddSuccess={handleAddSuccess} />
        </div>
    );
}



