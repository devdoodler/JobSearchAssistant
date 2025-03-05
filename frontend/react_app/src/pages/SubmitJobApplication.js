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

            navigate("/");

        } catch (error) {
            setError("Error submitting job application: " + error.response?.data?.error || error.message);
        }
    };

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth is zero-indexed
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}`;
    };

    const handleDateChange = (e) => {
        const newDate = e.target.value;
        setSubmitDate(newDate);
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
                    ></textarea>
                </div>
                <button type="submit">Submit Application</button>
            </form>
            {error && <div>{error}</div>}
        </div>
    );
}
