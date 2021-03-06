# copyright 2011-2012 Stefano Karapetsas <stefano@karapetsas.com>

# This file is part of AutoMate.
#
# AutoMate is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# AutoMate is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with AutoMate.  If not, see <http://www.gnu.org/licenses/>.


import os
import subprocess
import time

class CowBuilder():
    
    def __init__(self, dist, arch, configfile, logfile, buildresult):
        
        self.dist = dist
        self.arch = arch
        self.configfile = configfile
        self.logfile = logfile
        self.buildresult = buildresult

    def create(self, output):
        
        command = ["/usr/sbin/cowbuilder", "--create"]
        command.extend(["--configfile", self.configfile])
        
        return self.execute(command, output)

    def update(self, output):
        
        command = ["/usr/sbin/cowbuilder", "--update"]
        command.extend(["--configfile", self.configfile])
        if self.logfile != None:
            command.extend(["--logfile", self.logfile + ".update"])
        command.extend(["--override-config"])
        
        return self.execute(command, output)

    def build(self, dsc, output):
        
        command = ["/usr/sbin/cowbuilder", "--build", dsc]
        command.extend(["--configfile", self.configfile])
        command.extend(["--logfile", self.logfile])
        command.extend(["--buildresult", self.buildresult])
        command.extend(["--debbuildopts", "-Zxz"])
        
        return self.execute(command, output)

    def execute(self, command, output):
        
        os.environ["DIST"] = self.dist
        os.environ["ARCH"] = self.arch
        if output:
            p = subprocess.Popen(command, shell=False)
        else:
            DEVNULL = open('/dev/null', 'w')
            p = subprocess.Popen(command, shell=False, stdout=DEVNULL, stderr=DEVNULL)
        while p.returncode is None:
            p.poll()
            time.sleep(1)
        #log = p.stdout.read()
        if not output:
            DEVNULL.close()
        return p.returncode
