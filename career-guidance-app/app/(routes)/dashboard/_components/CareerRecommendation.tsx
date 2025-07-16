"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent } from "@/components/ui/card";

function CareerRecommendation() {
  const [formData, setFormData] = useState({
    interest: "",
    subject: "",
    skills: "",
    goals: "",
  });

  const [recommendation, setRecommendation] = useState("");
  const [showForm, setShowForm] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = () => {
    const { interest, subject, skills, goals } = formData;

    // 🔍 Decision Tree Logic (customized)
    if (
      interest.includes("technology") &&
      subject === "science" &&
      skills.includes("coding")
    ) {
      setRecommendation("Recommended Career: Software Engineer");
    } else if (interest.includes("design") && skills.includes("creativity")) {
      setRecommendation("Recommended Career: UI/UX Designer");
    } else if (interest.includes("business") && goals.includes("startup")) {
      setRecommendation("Recommended Career: Entrepreneur");
    } else {
      setRecommendation("Explore more paths based on your interests.");
    }
  };

  if (!showForm) {
    return (
      <Card className="mt-8 p-6 bg-white shadow-md rounded-xl text-center">
        <CardContent>
          <h3 className="text-xl font-bold mb-2">Start Your Career Journey</h3>
          <p className="text-sm text-gray-600 mb-4">
            Answer a few questions to get your personalized career
            recommendation.
          </p>
          <Button onClick={() => setShowForm(true)}>Start</Button>
        </CardContent>
      </Card>
    );
  }

  return (
    <Card className="mt-8 p-5 bg-white shadow-md rounded-xl">
      <CardContent>
        <h3 className="text-lg font-bold mb-4">Career Recommendation Form</h3>
        <div className="grid gap-4">
          <div>
            <Label>Interest Area</Label>
            <Input
              type="text"
              name="interest"
              placeholder="e.g., technology, design, business"
              onChange={handleChange}
              value={formData.interest}
            />
          </div>
          <div>
            <Label>Major Subject</Label>
            <Input
              type="text"
              name="subject"
              placeholder="e.g., science, management"
              onChange={handleChange}
              value={formData.subject}
            />
          </div>
          <div>
            <Label>Top Skills</Label>
            <Input
              type="text"
              name="skills"
              placeholder="e.g., coding, creativity, leadership"
              onChange={handleChange}
              value={formData.skills}
            />
          </div>
          <div>
            <Label>Career Goals</Label>
            <Input
              type="text"
              name="goals"
              placeholder="e.g., startup, research, freelancing"
              onChange={handleChange}
              value={formData.goals}
            />
          </div>

          <Button className="w-fit mt-2" onClick={handleSubmit}>
            Get Recommendation
          </Button>

          {recommendation && (
            <p className="mt-4 text-green-600 font-semibold">
              {recommendation}
            </p>
          )}
        </div>
      </CardContent>
    </Card>
  );
}

export default CareerRecommendation;
