import LastApplications from "./modules/LastApplications";
import EventApplicationsPieChart from "./modules/EventApplicationsPieChart";
import SubmitApplicationsChart from "./modules/SubmitApplicationsChart";

export default function Homepage() {
    return (
        <>
            <div className="container">
                <div className="left-div">
                    <LastApplications />
                </div>
                <div className="right-div">
                    <EventApplicationsPieChart />
                    <SubmitApplicationsChart />
                </div>
            </div>
        </>
    );
}
