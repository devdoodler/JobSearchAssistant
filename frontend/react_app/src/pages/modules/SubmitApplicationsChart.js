import { useEffect, useState } from 'react';
import request from "../../utils/request";
import './SubmitApplicationChart.scss';

export default function EventBarChart() {
    const [chartData, setChartData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/job-application/list/submit/total');
                setLoading(false);
                setChartData(response.data.jobApplicationsSubmitTotal);
            } catch (error) {
                console.log('Error fetching data', error);
                setError('Error fetching data');
            } finally {
                setLoading(false);
            }
        }

        fetchData();
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>{error}</div>;

    const maxSubmits = Math.max(...chartData.map(item => item.totalSubmits));

    return (
        <>
            <h3>Job application submit daily statistics</h3>
            <div className="bar-chart">
                {chartData.map((item) => {
                    const barHeight = (item.totalSubmits / maxSubmits) * 100;
                    return (
                        <div key={item.submitDate} className="bar" style={{height: `100%`}}>
                            <div
                                className="bar-inner-top"
                                style={{height: `${100-barHeight}%`}}
                            ></div>
                            <div
                                className="bar-inner"
                                style={{height: `${barHeight}%`}}
                            ></div>
                            <span>{item.submitDate}</span>
                        </div>
                    );
                })}
            </div>
        </>
    );
}
