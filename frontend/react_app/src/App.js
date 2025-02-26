import {BrowserRouter, Route, Routes} from 'react-router';

import './styles.css';
import Homepage from "./pages/Homepage";

function App() {
    return (
        <div className="App">
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Homepage />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

export default App;
