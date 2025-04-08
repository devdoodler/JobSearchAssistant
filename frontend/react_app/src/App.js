import {BrowserRouter, Route, Routes} from 'react-router';
import './styles.scss';
import Homepage from "./pages/Homepage";
import Header from './pages/Header';
import JobApplication from './pages/JobApplication';
import RejectJobApplication from "./pages/RejectJobApplication";
import SubmitJobApplication from "./pages/SubmitJobApplication";
import JobApplicationDetails from "./pages/JobApplicationDetails";
import JobApplicationList from "./pages/JobApplicationList";
import ScheduleJobApplication from "./pages/ScheduleJobApplication";
import WasHeldJobApplication from "./pages/WasHeldJobApplication";


export default function App() {
    return (
        <div className="App">
            <BrowserRouter>
                <Header/>
                <div style={{paddingTop: "130px"}}>
                    <Routes>
                        <Route path="/" element={<Homepage/>}/>
                        <Route path="/job-application" element={<JobApplication/>}/>
                        <Route path="/job-application/list" element={<JobApplicationList/>}/>
                        <Route path="/job-application/reject/:jobId" element={<RejectJobApplication/>}/>
                        <Route path="/job-application/schedule/:jobId" element={<ScheduleJobApplication/>}/>
                        <Route path="/job-application/held/:jobId/:interviewId" element={<WasHeldJobApplication/>}/>
                        <Route path="/job-application/submit/:jobId" element={<SubmitJobApplication/>}/>
                        <Route path="/job-application/:id" element={<JobApplicationDetails/>}/>
                    </Routes>
                </div>
            </BrowserRouter>
        </div>
);
}

