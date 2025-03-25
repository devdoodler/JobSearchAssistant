import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router";
import request from "../utils/request";

export default function ScheduleJobApplication() {
    const { jobId } = useParams();
    const navigate = useNavigate();
    const [scheduleDate, setScheduleDate] = useState("");
    const [interviewType, setInterviewType] = useState("Phone");
    const [comment, setComment] = useState("");
    const [error, setError] = useState("");


    const handleSchedule = async (e) => {
        e.preventDefault();

        const interviewData = {
            id: jobId,
            scheduleDate,
            interviewType,
            comment,
        };

        try {
            await request.post("/job-application/interview/schedule", interviewData);
            setError(null);
            navigate(`/job-application/${jobId}`); // Redirect back to Job Application Details page
        } catch (error) {
            setError("Error scheduling interview: " + (error.response?.data?.error || error.message));
        }
    };

    useEffect(() => {
    }, [jobId]);

    return (
        <div style={{paddingTop: "130px"}}>
            <h2>Schedule Interview for Job Application</h2>
            <form onSubmit={handleSchedule}>
                <div>
                    <label>Schedule Date</label>
                    <input
                        type="datetime-local"
                        value={scheduleDate}
                        onChange={(e) => setScheduleDate(e.target.value)}
                        required
                    />
                </div>
                <div>
                    <label>Interview Type</label>
                    <select
                        value={interviewType}
                        onChange={(e) => setInterviewType(e.target.value)}
                    >
                        <option value="Phone">Phone</option>
                        <option value="In-person">In-person</option>
                        <option value="Video">Video</option>
                    </select>
                </div>
                <div>
                    <label>Comment</label>
                    <textarea
                        value={comment}
                        onChange={(e) => setComment(e.target.value)}
                    ></textarea>
                </div>
                <button type="submit">Schedule Interview</button>
            </form>
            {error && <div>{error}</div>}
        </div>
    );
}
