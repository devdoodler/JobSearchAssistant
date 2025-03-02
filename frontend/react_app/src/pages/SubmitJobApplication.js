import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router";
import request from "../utils/request";

export default function SubmitJobApplication() {
    const { jobId } = useParams();
    const [comment, setComment] = useState("");
    const [submitDate, setSubmitDate] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await request.post(`/job-application/submit`, {
                id: jobId,
                submitDate,
                comment,
            });

            navigate("/home");//TODO: add success page

        } catch (error) {
            setError("Error submitting job application: " + error.response?.data?.error || error.message);
        }
    };

    useEffect(() => {
    }, [jobId]);

    return (
        <div>
            <h2>Submit Job Application</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Submit Date</label>
                    <input
                        type="datetime-local"
                        value={submitDate}
                        onChange={(e) => setSubmitDate(e.target.value)}
                        required
                    />
                </div>
                <div>
                    <label>Comment</label>
                    <textarea
                        value={comment}
                        onChange={(e) => setComment(e.target.value)}
                        required
                    ></textarea>
                </div>
                <button type="submit">Submit Application</button>
            </form>
            {error && <div>{error}</div>}
        </div>
    );
}
