import React from 'react';

interface GlobalLoaderProps {
  isItLoading: boolean;
}

const GlobalLoaderMini: React.FC<GlobalLoaderProps> = ({ isItLoading }) => {
  if (!isItLoading) {
    return null; // Don't render anything if not loading
  }

  return (
    <div className="text-center p-3">
      {/* Add your loading animation or spinner here */}
      <div className="loader" style={{display:'inline-block'}}></div>
    </div>
  );
};

export default GlobalLoaderMini;
