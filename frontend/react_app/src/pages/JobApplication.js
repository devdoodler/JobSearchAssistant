import { useState } from 'react';
import { useNavigate } from "react-router";
import AddJobApplication from './AddJobApplication';

export default function JobApplication() {
    const [jobApplicationId, setJobApplicationId] = useState(null);
    const navigate = useNavigate();

    const handleAddSuccess = (jobId) => {
        setJobApplicationId(jobId);
        navigate(`/job-application/submit/${jobId}`);
    };

    return (
        <div style={{ paddingTop: "130px" }}>
            <AddJobApplication onAddSuccess={handleAddSuccess} />
        </div>
    );
}



