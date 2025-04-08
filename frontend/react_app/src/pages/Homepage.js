import LastApplications from "./modules/LastApplications";
import EventApplicationsPieChart from "./modules/EventApplicationsPieChart";

export default function Homepage() {
    return (
        <>
            <div className="container">
                <div className="left-div">
                    <LastApplications />
                </div>
                <div className="right-div">
                    <EventApplicationsPieChart />
                </div>
            </div>
        </>
    );
}
