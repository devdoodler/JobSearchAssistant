import {BrowserRouter, Route, Routes} from 'react-router';
import './styles.css';
import Homepage from "./pages/Homepage";
import Header from './pages/Header';
import JobApplication from './pages/JobApplication';
import SubmitJobApplication from "./pages/SubmitJobApplication";


export default function App() {
    return (
        <div className="App">
            <BrowserRouter>
                <Header />
                <Routes>
                    <Route path="/" element={<Homepage />} />
                    <Route path="/job-application" element={<JobApplication />} />
                    <Route path="/job-application/submit/:jobId" element={<SubmitJobApplication />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

