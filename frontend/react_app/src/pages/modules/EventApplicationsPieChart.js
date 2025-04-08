import { useEffect, useState } from 'react';
import request from "../../utils/request";
import { getStatus } from '../../utils/statusUtils';

export default function EventPieChart() {
    const [chartData, setChartData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        async function fetchData() {
            try {
                const response = await request.get('/job-application/list/event/count');
                setLoading(false);
                setChartData(response.data.jobApplicationsEventCount);
            } catch (error) {
                console.log('Error fetching data', error);
            } finally {
                setLoading(false);
            }
        }

        fetchData();
    }, []);

    if (loading) return <div>Loading...</div>;
    if (error) return <div>{error}</div>;

    const getColorFromClass = (className) => {
        const dummyDiv = document.createElement('div');
        dummyDiv.className = className;
        document.body.appendChild(dummyDiv);
        const color = getComputedStyle(dummyDiv).color;
        document.body.removeChild(dummyDiv);
        return color;
    };

    const generateEventChart = () => {
        if (!chartData || chartData.length === 0) return 'transparent';

        const totalSum = chartData.reduce((sum, item) => sum + item.total, 0);

        let angleStart = 0;
        const gradientStops = chartData.map(item => {
            const proportion = item.total / totalSum;
            const angleEnd = angleStart + proportion * 360;

            const { className } = getStatus(item.event);
            const color = getColorFromClass(className);

            const borderWidth = 2;
            const stop = `${color} ${angleStart}deg ${angleEnd - borderWidth}deg, white ${angleEnd - borderWidth}deg ${angleEnd}deg`;
            angleStart = angleEnd;

            return stop;
        });

        return `radial-gradient(white 40%, transparent 41%), conic-gradient(${gradientStops.join(', ')})`;
    };

    const generateLegend = () => {
        if (!chartData || chartData.length === 0) return null;

        const totalSum = chartData.reduce((sum, item) => sum + item.total, 0);
        const sortedData = [...chartData].sort((a, b) => b.total - a.total);

        return sortedData.map((item, index) => {
            const proportion = item.total / totalSum;
            const { status, className } = getStatus(item.event);
            const color = getColorFromClass(className);

            return (
                <div
                    key={item.event}
                    style={{
                        display: 'flex',
                        alignItems: 'center',
                        marginBottom: '8px',
                        width: '48%',
                    }}
                >
                    <div
                        style={{
                            width: '12px',
                            height: '12px',
                            backgroundColor: color,
                            marginRight: '8px',
                            borderRadius: '50%',
                        }}
                    ></div>
                    <div style={{ fontSize: '10px', fontWeight: 'bold' }}>
                        {status} ({item.total}) - {Math.round((proportion + Number.EPSILON) * 100)}%
                    </div>
                </div>
            );
        });
    };

    return (
        <>
            <h3>Job application states </h3>
            <div
                style={{
                    margin: '20px',
                    width: '200px',
                    height: '200px',
                    background: generateEventChart(),
                    borderRadius: '50%',
                    position: 'relative',
                }}
            />
            <div
                style={{
                    marginTop: '20px',
                    display: 'flex',
                    flexWrap: 'wrap',
                    justifyContent: 'space-between',
                }}
            >
                {generateLegend()}
            </div>
        </>
    );
}
