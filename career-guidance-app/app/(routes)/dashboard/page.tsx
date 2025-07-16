import React from "react";
import WelcomeBanner from "./_components/WelcomeBanner";
import Tools from "./_components/Tools";
import CareerRecommendation from "./_components/CareerRecommendation";

function Dashboard() {
  return (
    <div>
      <WelcomeBanner />
      <CareerRecommendation />
      <Tools />
    </div>
  );
}

export default Dashboard;
