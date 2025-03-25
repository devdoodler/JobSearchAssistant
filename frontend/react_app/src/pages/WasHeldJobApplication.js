import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router";
import request from "../utils/request";

export default function WasHeldJobApplication() {
    const { jobId } = useParams();
    const { interviewId } = useParams();
    const [comment, setComment] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();


    const handleSubmit = async (e) => {
        e.preventDefault();

        const interviewData = {
            id: jobId,
            interviewId: interviewId,
            comment: comment
        };

        try {
            await request.post("/job-application/interview/held", interviewData);
            setError(null);
            navigate(`/job-application/${jobId}`);
        } catch (error) {
            setError("Error submiting was held interview: " + (error.response?.data?.error || error.message));
        }
    };

    useEffect(() => {
    }, [jobId]);

    return (
        <div style={{paddingTop: "130px"}}>
            <h2>Mark Interview as Held</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Comment</label>
                    <textarea
                        value={comment}
                        onChange={(e) => setComment(e.target.value)}
                    />
                </div>
                <button type="submit">Submit</button>
            </form>
            {error && <div>{error}</div>}
        </div>
    );
}
