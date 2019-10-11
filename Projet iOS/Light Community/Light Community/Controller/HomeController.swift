//
//  HomeController.swift
//  Light Community
//
//  Created by Samuel Clauzon on 09/06/2019.
//  Copyright © 2019 Samuel Clauzon. All rights reserved.
//

import UIKit
import CoreData

class HomeController: UIViewController {

    @IBOutlet weak var loadingView: UIView!
    
    private var appKey: String = "192a360f1358b1b7c5c0399fa8683a92"
    private var urlPath: String!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        loadingView.isHidden = false
        
        if (Student.dataLoaded == false) {
            urlPath = "http://light-community.fr/mysql-app/load_data.php?app_key=\(appKey)&email=\(Login.email)&password=\(Login.password)"
            
            let url: URL = URL(string: urlPath)!
            let defaultSession = Foundation.URLSession(configuration: URLSessionConfiguration.default)
            
            let task = defaultSession.dataTask(with: url) { (data, response, error) in
                if (error != nil) {
                    DispatchQueue.main.async {
                        Student.isConnected = false
                        
                        self.loadMainController()
                    }
                } else {
                    LC.parseJSONLoadData(data!)
                }
            }
            task.resume()
            
            let name = Notification.Name("studentDataLoadedNotification")
            NotificationCenter.default.addObserver(self, selector: #selector(loadDataManager), name: name, object: nil)
        }
    }
    
    @objc func loadDataManager() {
        if (Student.isConnected) {
            loadingView.isHidden = true
            
            Student.dataLoaded = true
            
        } else {
            let request: NSFetchRequest<User> = User.fetchRequest()
            guard let user = try? AppDelegate.viewContext.fetch(request) else {
                return
            }
            for userData in user {
                userData.savedEmail! = ""
                userData.savedPassword! = ""
            }
            try? AppDelegate.viewContext.save()
            loadMainController()
        }
    }
    
    func loadMainController() {
        let storyboard = UIStoryboard(name: "Main", bundle: nil)
        let controller = storyboard.instantiateViewController(withIdentifier: "ViewController")
        self.present(controller, animated: true, completion: nil)
    }

}
