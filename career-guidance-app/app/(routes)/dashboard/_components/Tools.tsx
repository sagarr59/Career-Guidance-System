const ToolsList = [
  {
    name: "AI Career Q&A Chat",
    desc: "Chat with AI Agent",
    icon: "/chatbot.png",
    button: "Let's Chat",
    path: "/career-chat",
  },
  {
    name: "AI Resume Analyzer",
    desc: "Improve your resume",
    icon: "/resume.png",
    button: "Analyze Now",
    path: "/resume-analyzer",
  },
  {
    name: "Career Roadmap Generator",
    desc: "Build your roadmap",
    icon: "/roadmap.png",
    button: "Generate Now",
    path: "/career-roadmap-generator",
  },
  {
    name: "Cover Letter Generator",
    desc: "Write a cover letter",
    icon: "/cover.png",
    button: "Create Now",
    path: "/cover-letter-generator",
  },
];

import Link from "next/link";

function Tools() {
  return (
    <div className="mt-7 p-5 bg-white rounded-xl shadow-md">
      <h2 className="font-bold text-xl mb-2">Available Tools</h2>
      <p className="text-gray-600 mb-4">
        Start building your career toolkit using the tools below.
      </p>

      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4">
        {ToolsList.map((tool, index) => (
          <div
            key={index}
            className="p-4 border rounded-lg flex items-center gap-4 hover:shadow-md transition"
          >
            <img src={tool.icon} alt={tool.name} className="w-12 h-12" />
            <div className="flex-1">
              <h3 className="font-semibold text-lg">{tool.name}</h3>
              <p className="text-sm text-gray-500">{tool.desc}</p>
            </div>
            <Link href={tool.path}>
              <button className="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
                {tool.button}
              </button>
            </Link>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Tools;
