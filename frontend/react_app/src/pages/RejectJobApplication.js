import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router";
import request from "../utils/request";

export default function RejectJobApplication() {
    const { jobId } = useParams();
    const [comment, setComment] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    const handleReject = async (e) => {
        e.preventDefault();

        try {
            await request.post(`/job-application/reject`, {
                id: jobId,
                comment,
            });
            setError(null);

            navigate(`/job-application/${jobId}`);
        } catch (error) {
            setError("Error submitting rejection: " + error.response?.data?.error || error.message);
        }
    };

    useEffect(() => {
    }, [jobId]);

    return (
        <div>
            <h2>Reject Job Application</h2>
            <form onSubmit={handleReject}>
                <div>
                    <label>Comment</label>
                    <textarea
                        value={comment}
                        onChange={(e) => setComment(e.target.value)}
                    ></textarea>
                </div>
                <button type="submit">Application was rejected</button>
            </form>
            {error && <div>{error}</div>}
        </div>
    );
}
