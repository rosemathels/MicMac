#!/usr/bin/python3
"""
@author: Farah BATTIKH
"""

import re
import gpstime as gps
import os
import RtklibProcess
import RtklibUtils as utils


class ManageProcess():
    def __init__(self):
        #The directory containing the sub-folders to be processed
        self.HomeDir = utils.get_receiver_path()+"/"

    def IsThereAnythingToDo(self):
        #List directory
        L = os.listdir(self.HomeDir)
        R = RtklibProcess.rtklib_process()
        #Assign paths
        R.projectPath = utils.get_project_path()
        R.observationPath =  utils.get_observation_path(R.projectPath)
        R.exeConfPath =     utils.get_exeConf_path(R.projectPath)

        #Process only no locked directories
        for d in L:
            if re.search('LOCKED',d):
                continue

            #The request xml file            
            RequestDir = self.HomeDir + d
            #Call the method of starting treatment
            R.process(RequestDir)
            #Rename the directory at the end of the processing
            #os.rename(RequestDir,RequestDir+'_LOCKED')



if __name__ == "__main__":

    t1 = gps.gpstime()
    
    print ("starting here procces path",os.getcwd())
    S = ManageProcess()
    S.IsThereAnythingToDo()
    
    t2 = gps.gpstime()
    print ('%.3f sec elapsed ' % (t2-t1))
