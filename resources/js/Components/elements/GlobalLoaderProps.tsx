import React from 'react';

interface GlobalLoaderProps {
  isLoading: boolean;
}

const GlobalLoader: React.FC<GlobalLoaderProps> = ({ isLoading }) => {
  if (!isLoading) {
    return null; // Don't render anything if not loading
  }

  return (
    <div className="global-loader">
      {/* Add your loading animation or spinner here */}
      <div className="loader"></div>
    </div>
  );
};

export default GlobalLoader;
