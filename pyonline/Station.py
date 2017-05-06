# -*- coding: utf-8 -*-
"""
Created on Sun Jan  8 23:41:17 2017

@author: farah
"""
import numpy as np
class Station():
    def __init__(self,nom , X = 0,Y = 0, Z = 0):
        self.nom = nom
        self.X = X
        self.Y = Y
        self.Z = Z
        self.last_dist=-1
    def calc_dist(self,Xrec,Yrec,Zrec):
        self.last_dist = np.sqrt(((self.X - Xrec)**(2 ))+ ((self.Y - Yrec)**(2)) +(self.Z- Zrec )**(2))
        return self.last_dist