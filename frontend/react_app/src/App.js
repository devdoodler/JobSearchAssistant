import {BrowserRouter, Route, Routes} from 'react-router';
import './styles.css';
import Homepage from "./pages/Homepage";
import Header from './pages/Header';
import JobApplication from './pages/JobApplication';
import RejectJobApplication from "./pages/RejectJobApplication";
import SubmitJobApplication from "./pages/SubmitJobApplication";
import JobApplicationDetails from "./pages/JobApplicationDetails";
import ScheduleJobApplication from "./pages/ScheduleJobApplication";
import WasHeldJobApplication from "./pages/WasHeldJobApplication";


export default function App() {
    return (
        <div className="App">
            <BrowserRouter>
                <Header />
                <Routes>
                    <Route path="/" element={<Homepage />} />
                    <Route path="/job-application" element={<JobApplication />} />
                    <Route path="/job-application/reject/:jobId" element={<RejectJobApplication />} />
                    <Route path="/job-application/schedule/:jobId" element={<ScheduleJobApplication />} />
                    <Route path="/job-application/held/:jobId/:interviewId" element={<WasHeldJobApplication />} />
                    <Route path="/job-application/submit/:jobId" element={<SubmitJobApplication />} />
                    <Route path="/job-application/:id" element={<JobApplicationDetails />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

