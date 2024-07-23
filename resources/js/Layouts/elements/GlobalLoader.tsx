import React from 'react';

const GlobalLoader: React.FC<{ isLoading: boolean }> = ({isLoading}) => {
    if (!isLoading) {
        return null; // Don't render anything if not loading
    }
    return (
        <div className="global-loader">
            <div className="loader"></div>
        </div>
    );
};
export default GlobalLoader;
