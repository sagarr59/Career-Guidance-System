import { Button } from "@/components/ui/button";

function WelcomeBanner() {
  return (
    <div className="p-5 bg-gradient-to-tr from-[#1e3a8a] via-[#3b82f6] to-[#06b6d4] rounded-xl shadow-md">
      <h2 className="text-white text-2xl font-bold">Career Guidance System</h2>
      <p className="text-white mt-2">
        Your personalized path to a brighter future starts here.
      </p>
      <Button variant={"outline"} className="mt-3">
        Let's Get Started
      </Button>
    </div>
  );
}

export default WelcomeBanner;
